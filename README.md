# Messaging App

A simple Laravel 10 application that sends SMS messages through a third-party webhook endpoint using queues and Redis.

## Requirements

* Docker Desktop
* Docker Compose

## Installation

Clone the repository and start the containers:

```bash
docker compose up -d --build
```

Install dependencies and run migrations:

```bash
docker compose exec app php artisan migrate --seed
```

## Environment

Update the `.env` file with the required values:

```env
INSIDER_WEBHOOK_URL=https://webhook.site/39b37bfe-7462-4ee1-be92-638ee50b51d9
INSIDER_AUTH_KEY=INS.me1x9uMcyYGlhKKQVPoc.bO3j9aZwRTOcA2Ywo
```

## How It Works

1. Messages are stored in the database.
2. The dispatch command retrieves pending messages.
3. Messages are pushed to the Redis queue.
4. Queue workers process messages in the background.
5. The application sends requests to the configured webhook endpoint.
6. Sent messages are marked as delivered and cached in Redis.

## Dispatch Messages

Dispatch pending messages:

```bash
docker compose exec app php artisan messages:dispatch
```

## Run Queue Worker

```bash
docker compose exec app php artisan queue:work
```

## API

Get sent messages:

```http
GET /api/v1/messages/sent
```

Example:

```json
{
    "data": [
        {
            "id": 1,
            "phone": "+123456789",
            "content": "Example message",
            "message_id": "uuid",
            "sent_at": "2026-06-17T10:50:21Z"
        }
    ]
}
```

## API Documentation

Swagger UI:

```text
http://localhost:8000/api/documentation
```

## Running Tests

```bash
docker compose exec app php artisan test
```

## Architecture

The project follows a simple layered architecture:

* Controllers handle HTTP requests
* Services contain business logic
* Repositories handle data access
* Jobs process messages asynchronously
* Redis is used for queues and caching

## Notes

* Messages are processed in batches of 2.
* Queue processing uses Redis.
* Sent message information is cached in Redis.
* API responses are versioned under `/api/v1`.

## Author

Gismat Mammadov
