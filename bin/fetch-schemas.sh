#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

mkdir -p bin/schemas
rm -f bin/schemas/*.schema.json bin/schemas/schema.zip

curl -sSL https://api.speedy.bg/v1/schema -o bin/schemas/schema.zip
unzip -q -o bin/schemas/schema.zip -d bin/schemas
rm bin/schemas/schema.zip

echo "Snapshotted $(ls bin/schemas/*.schema.json | wc -l | tr -d ' ') schemas to bin/schemas/"
