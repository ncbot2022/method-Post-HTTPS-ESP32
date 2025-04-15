#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "TEKNOLAB Office";
const char* password = "selamatdatang";

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  Serial.print("Menghubungkan ke WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nTerhubung ke WiFi");
}

void loop() {
  // Kosongkan atau sesuaikan sesuai kebutuhan
  Post_Data();
}

unsigned prevMillisPost=0;
void Post_Data() {
  if (WiFi.status() == WL_CONNECTED) {
    if(millis() - prevMillisPost >= 1000){
      HTTPClient http;

      StaticJsonDocument<512> doc;
      doc["Volt"] = 220.5;
      doc["Current"] = 0.45;
      doc["Freq"] = 50.1;
      doc["Pfaktor"] = 0.95;
      doc["ActiveEnergy"] = 12.3;
      doc["ReactiveEnergy"] = 5.4;
      doc["ActivePower"] = 98.7;
      doc["ApparentPower"] = 110.2;
      doc["ApparentEnergy"] = 10.5;
      doc["ReactivePower"] = 4.8;

      String jsonString;
      serializeJson(doc, jsonString);

      http.begin("http://192.168.1.27/PowerMode/backand/savedata.php");  
      http.addHeader("Content-Type", "application/json"); 

      int httpResponseCode = http.POST(jsonString);  
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);

      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("Response:");
        Serial.println(response);
      } else {
        Serial.print("Error pada POST: ");
        Serial.println(httpResponseCode);
      }
      http.end(); 
      prevMillisPost = millis();
    }
  } else {
    Serial.println("Tidak terhubung ke WiFi");
  }
}
