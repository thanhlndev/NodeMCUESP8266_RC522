# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# RFID MFRC522 / RC522 Library : https://github.com/miguelbalboa/rfid # 
#                                                                     # 
#                 Installation :                                      # 
# NodeMCU ESP8266/ESP12E    RFID MFRC522 / RC522                      #
#         D2       <---------->   SDA/SS                              #
#         D5       <---------->   SCK                                 #
#         D7       <---------->   MOSI                                #
#         D6       <---------->   MISO                                #
#         GND      <---------->   GND                                 #
#         D1       <---------->   RST                                 #
#         3V/3V3   <---------->   3.3V                                #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN D2
#define RST_PIN D1
#define ON_BOARD_LED 2

MFRC522 rfidReader(SS_PIN, RST_PIN);
WiFiClient wifiClient;

const char* wifiSsid = "Bac Hoa"; // Tên mạng Wi-Fi
const char* wifiPassword = "hoalan78"; // Mật khẩu Wi-Fi
const char* serverUrl = "http://192.168.1.17/test/getUID.php"; // URL server
const char* deviceLocation = "Home2"; // Định nghĩa vị trí cố định (Home1 hoặc Home2)

void setup() {
  Serial.begin(115200);
  pinMode(ON_BOARD_LED, OUTPUT);
  digitalWrite(ON_BOARD_LED, HIGH); // Tắt LED ban đầu
  SPI.begin();
  rfidReader.PCD_Init();
  Serial.println("RFID reader initialized");

  // Kết nối Wi-Fi
  WiFi.begin(wifiSsid, wifiPassword);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(ON_BOARD_LED, LOW);
    delay(250);
    digitalWrite(ON_BOARD_LED, HIGH);
    delay(250);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi");
  Serial.println("NodeMCU IP: " + WiFi.localIP().toString());
  Serial.println("Server URL: " + String(serverUrl));
  Serial.println("Device Location: " + String(deviceLocation));
  Serial.println("Please tag a card");
}

void loop() {
  // Kiểm tra thẻ RFID
  if (!rfidReader.PICC_IsNewCardPresent() || !rfidReader.PICC_ReadCardSerial()) {
    return;
  }

  digitalWrite(ON_BOARD_LED, LOW); // Bật LED khi đọc thẻ
  String cardUid = "";
  for (byte i = 0; i < rfidReader.uid.size; i++) {
    cardUid += String(rfidReader.uid.uidByte[i] < 0x10 ? "0" : "") + String(rfidReader.uid.uidByte[i], HEX);
  }
  cardUid.toUpperCase();
  Serial.println("UID: " + cardUid);

  // Gửi dữ liệu nếu Wi-Fi kết nối
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient httpClient;
    httpClient.setTimeout(5000); // Tăng thời gian chờ lên 5 giây
    Serial.println("Attempting connection to server...");
    httpClient.begin(wifiClient, serverUrl);
    httpClient.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String postData = "UIDresult=" + cardUid + "&location=" + deviceLocation;
    Serial.println("Sending: " + postData);

    int httpResponseCode = httpClient.POST(postData);
    if (httpResponseCode > 0) {
      Serial.println("HTTP Code: " + String(httpResponseCode));
      String serverResponse = httpClient.getString();
      Serial.println("Response: " + serverResponse);
    } else {
      Serial.println("HTTP Error: " + httpClient.errorToString(httpResponseCode));
      if (httpResponseCode == -1) {
        Serial.println("Connection failed, check server IP or network.");
      } else if (httpResponseCode == -2) {
        Serial.println("Timeout occurred, server may be unresponsive.");
      } else if (httpResponseCode == -3) {
        Serial.println("Invalid response, check server configuration.");
      }
    }
    httpClient.end();
  } else {
    Serial.println("WiFi Disconnected, attempting reconnect...");
    WiFi.reconnect();
    delay(1000);
  }

  rfidReader.PICC_HaltA(); // Tạm dừng đọc thẻ
  digitalWrite(ON_BOARD_LED, HIGH); // Tắt LED
  delay(1000); // Chờ 1 giây trước khi đọc thẻ tiếp theo
}