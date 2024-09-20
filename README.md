# Project Management Task

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Development Server](#running-the-development-server)
- [Building for Production](#building-for-production)
- [Deploying to AWS](#deploying-to-aws)
- [Environment Variables](#environment-variables)

## Prerequisites

Make sure you have the following installed:

- PHP (>= 8.0)
- Composer
- Node.js and npm (for front-end assets)
- MySQL or another database server

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Jesutofunmi2/simbrellangBackend
   cd simbrellangBackend

   composer install

   cp .env.example .env
   nano .env

   php artisan key:generate

   php artisan migrate
