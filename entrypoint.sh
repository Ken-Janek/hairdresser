#!/bin/sh
set -e

# Get PORT from environment or use default
PORT=${PORT:-8080}

# Start PHP built-in server
exec php -S 0.0.0.0:${PORT} -t public/
