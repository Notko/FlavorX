# FlavorX API

Welcome to FlavorX API! This API provides the backend functionality for managing users, recipes, and interactions with
recipes.

## Table of Contents

- [Features](#features)
- [Authentication](#authentication)
- [Endpoints](#endpoints)
    - [POST](#post-routes)
    - [GET](#get-routes)
    - [PATCH](#patch-routes)
    - [DELETE](#delete-routes)
- [Error Handling](#error-handling)

## Features

- User registration and login with JWT authentication.
- User profile management, including updates and deletions.
- Recipe creation and management.
- Liking and unliking recipes.
- Adding and retrieving comments for recipes.

## Authentication

This API uses JSON Web Tokens (JWT) for authentication. To obtain a token, you can use the `/login` endpoint with your
email and password. Once you have a token, include it in the `Authorization` header as a Bearer token for protected
routes.

## Endpoints

### POST Routes

- **User Registration:** `/user/create`
- **User Login:** `/user/login`

- **Recipe Creation:** `/recipe/new` (Protected)
- **Recipe Like:** `/recipe/like` (Protected)

- **Add Comment:** `/comment/add` (Protected)

### GET Routes

- **User Profile:** `/user/{id}`
- **Recipe Details:** `/recipe/{id}`
- **Get Comments for a Recipe:** `/recipe/{id}/comments`

- **Recipe Listing:** `/recipes` (Optional: Query parameters for limiting and offsetting results)

### PATCH Routes

- **Recipe Update:** `/recipe/update` (Protected)
- **User Profile Update:** `/user/update` (Protected)

### DELETE Routes

- **User Deletion:** `/user/delete` (Protected)
- **Recipe Deletion:** `/recipe/delete` (Protected)
- **Recipe Unlike:** `/recipe/unlike` (Protected)
- **Comment Deletion:** `/comment/delete` (Protected)

## Error Handling

This API provides informative error responses in case of issues, such as validation errors, unauthorized access, or
resource not found. Ensure to handle these errors gracefully in your frontend application.

Feel free to explore and build upon this API for your projects. Happy coding!
