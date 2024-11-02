# PBDS

## Repo Structure

```text
.
├── docs                                  # Documents Folder.
├── source                                # Contains Source Code of Mobile & Web Apps.
└── README.MD                             # Readme file.
└── LICENCE                               # Licence file.
```

## Introduction
The Organ Donation App is an integrated mobile and web-based platform designed to facilitate communication and management between patients seeking organ transplants and hospitals providing them. This app aims to streamline the organ donation process by offering essential functionalities, such as organ match identification, organ requests, and direct communication with hospitals. The platform is structured into two main components:

Mobile Application: Primarily designed for end-users (patients), the mobile app enables users to search for organ matches, request organs, and communicate directly with hospitals. It provides a user-friendly experience that guides patients through requesting an organ, receiving match notifications, and sending messages to hospital representatives.

Backend Management Panel: Accessible to hospital administrators and medical personnel, the backend panel serves as a centralized hub for managing patient requests, responding to messages, approving or denying organ requests, and monitoring overall user interactions. This panel streamlines administrative workflows, ensuring that hospital staff can manage cases efficiently and reach out to patients seamlessly.

![Organ Donation App Banner](docs/media/images/0-banner-image.jpg)


# Setup Web App

Here, we are setting up the Web App. Later, we will provide instructions to set up the mobile app.

**Web App Source Folder:** `source/web-app`



### Step 01: Setup database 

```
php artisan migrate
```

Note: A populated database is already available and can be found inside docs/organ_app.sql. You can also import it manually if needed.

### Step 02: Setup .env file from .env

```
APP_NAME=OrganApp
APP_ENV=local
APP_KEY=base64:yj7t43y+JJ+syX8zNcf+YIgEyvgIMRAm23RiDRPJavU=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=organ_app
DB_USERNAME=root
DB_PASSWORD=
```

### Step 03: Install Composer Dependencies

```
composer install
```

### Step 04: Generate an APP_KEY

```
php artisan key:generate
```

