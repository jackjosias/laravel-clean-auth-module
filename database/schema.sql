-- schema.sql - PostgreSQL 16
-- Generated reference schema for Laravel Clean Auth Module.

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE INDEX idx_users_email ON users(email);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id UUID NULL REFERENCES users(id) ON DELETE CASCADE,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);
