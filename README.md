# Project Setup Instructions

## Prerequisites
- dashboard user: admin@admin.com 
- dashboard pass : 123456789

-To run this project successfully, you need to configure the following services:

- **Twilio** for SMS or messaging services.
- **Firebase** for push notifications.

## 1. Twilio Configuration
You need to provide your Twilio credentials in the `.env` file. Add the following lines with your Twilio credentials:

```env
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_phone_number
```
Make sure you replace your_twilio_sid, your_twilio_token, and your_twilio_phone_number with your actual Twilio details. You can get these credentials from your Twilio Console.

## 2. Firebase Configuration
For Firebase push notifications, you need to add the Firebase credentials.
```
Place the firebase_credentials.json file in the app/services directory of the project.
```
Make sure this file contains the necessary credentials to authenticate with Firebase Cloud Messaging (FCM).

