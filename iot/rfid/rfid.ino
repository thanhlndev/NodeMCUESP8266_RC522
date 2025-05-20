#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <time.h>

#define SS_PIN D2
#define RST_PIN D1
#define ON_BOARD_LED 2

MFRC522 rfidReader(SS_PIN, RST_PIN);
WiFiClient wifiClient;

const char* wifiSsid = "Bac Hoa";
const char* wifiPassword = "hoalan78";
const char* serverUrl = "http://192.168.1.17/NodeMCU_RC522_Mysql/getUID.php";

void setup() {
  Serial.begin(115200);
  pinMode(ON_BOARD_LED, OUTPUT);
  digitalWrite(ON_BOARD_LED, HIGH);
  SPI.begin();
  rfidReader.PCD_Init();
  Serial.println("RFID reader initialized");

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
  Serial.println("Please tag a card");
}

void loop() {
  if (!rfidReader.PICC_IsNewCardPresent() || !rfidReader.PICC_ReadCardSerial()) {
    return;
  }

  digitalWrite(ON_BOARD_LED, LOW);
  String cardUid = "";
  for (byte i = 0; i < rfidReader.uid.size; i++) {
    cardUid += String(rfidReader.uid.uidByte[i] < 0x10 ? "0" : "") + String(rfidReader.uid.uidByte[i], HEX);
  }
  cardUid.toUpperCase();
  Serial.println("[" + String(millis()) + "] UID: " + cardUid);

  if (WiFi.status() == WL_CONNECTED) {
    sendUidToServer(cardUid);
  } else {
    Serial.println("[" + String(millis()) + "] WiFi Disconnected, attempting reconnect...");
    WiFi.reconnect();
    delay(1000);
  }

  rfidReader.PICC_HaltA();
  digitalWrite(ON_BOARD_LED, HIGH);
  delay(1000);
}

void sendUidToServer(String uid) {
  HTTPClient httpClient;
  httpClient.setTimeout(5000);
  Serial.println("[" + String(millis()) + "] Attempting connection to server...");
  httpClient.begin(wifiClient, serverUrl);
  httpClient.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String postData = "UIDresult=" + uid;
  Serial.println("[" + String(millis()) + "] Sending: " + postData);

  int attempts = 0;
  while (attempts < 3) {
    int httpResponseCode = httpClient.POST(postData);
    if (httpResponseCode > 0) {
      Serial.println("[" + String(millis()) + "] HTTP Code: " + String(httpResponseCode));
      String serverResponse = httpClient.getString();
      Serial.println("[" + String(millis()) + "] Response: " + serverResponse);
      break;
    } else {
      Serial.println("[" + String(millis()) + "] HTTP Error: " + httpClient.errorToString(httpResponseCode));
      attempts++;
      delay(1000);
    }
  }
  httpClient.end();
}