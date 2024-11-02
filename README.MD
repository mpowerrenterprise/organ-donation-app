# PBDS

## Repo Structure

```text
.
├── docs                                  # Documents Folder.
├── source                                # Contains Source Code of Mobile & Web Apps
└── README.MD                             # Readme file.
└── LICENCE                               # Licence file.
```

## Introduction
The Organ Donation App is an integrated mobile and web-based platform designed to facilitate communication and management between patients seeking organ transplants and hospitals providing them. This app aims to streamline the organ donation process by offering essential functionalities, such as organ match identification, organ requests, and direct communication with hospitals. The platform is structured into two main components:

Mobile Application: Primarily designed for end-users (patients), the mobile app enables users to search for organ matches, request organs, and communicate directly with hospitals. It provides a user-friendly experience that guides patients through requesting an organ, receiving match notifications, and sending messages to hospital representatives.

Backend Management Panel: Accessible to hospital administrators and medical personnel, the backend panel serves as a centralized hub for managing patient requests, responding to messages, approving or denying organ requests, and monitoring overall user interactions. This panel streamlines administrative workflows, ensuring that hospital staff can manage cases efficiently and reach out to patients seamlessly.


## Setup

### Step 01: Setup database 

```
The database for the project is already populated and can be found on the 
current server, ensuring that all necessary data is up-to-date. However, 
an outdated local version of the database is also included in the project files, 
located in the "source/database/databases" folder. 
```

### Step 02: Setup .env file from .env.example

```
APP_NAME=PBDS
APP_ENV=local
APP_KEY=base64:Bxy63nNhuFe0GtGIUM8Ttp8RFqX7opkK9FnyORfewe4=
APP_DEBUG=true
APP_TIMEZONE=Asia/Colombo
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pbds_lease
DB_USERNAME=root
DB_PASSWORD=
```

### Step 03: Install Composer Dependencies

```
composer install
```

### Step 04: Install NPM Dependencies

```
npm install
```

### Step 05: Generate an APP_KEY

```
php artisan key:generate
```

