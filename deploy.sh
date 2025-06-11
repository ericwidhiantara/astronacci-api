#!/bin/bash

# Build ulang tanpa cache
#docker compose build --no-cache
docker compose build


# Jalankan container di background
docker compose up -d
