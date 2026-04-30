#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

mkdir -p bin/schemas
rm -f bin/schemas/*.schema.json bin/schemas/schema.zip

curl -sSL https://api.speedy.bg/v1/schema -o bin/schemas/schema.zip
unzip -q -o bin/schemas/schema.zip -d bin/schemas
rm bin/schemas/schema.zip

# --- Supply chain integrity gate ---------------------------------------------
# The Speedy API regenerates the schema bundle on every request (different ZIP
# bytes each time) so we cannot pin the bundle's checksum. Instead we hash the
# extracted schema content: sorted per-file SHA-256 lines, then SHA-256 of that
# manifest. Stable across re-fetches when the API is unchanged; loud warning
# when content drifts. Approve a real upstream change with:
#     bash bin/fetch-schemas.sh && bin/.compute-schema-hash > bin/schema.sha256
NEW_HASH=$( ( cd bin/schemas && shasum -a 256 *.schema.json | LC_ALL=C sort ) | shasum -a 256 | awk '{print $1}' )
BASELINE_FILE="bin/schema.sha256"

if [[ ! -f "$BASELINE_FILE" ]]; then
    echo "$NEW_HASH" > "$BASELINE_FILE"
    echo "[speedy] First-run baseline written to $BASELINE_FILE: $NEW_HASH"
else
    STORED_HASH=$(tr -d '[:space:]' < "$BASELINE_FILE")
    if [[ "$NEW_HASH" == "$STORED_HASH" ]]; then
        echo "[speedy] Schema checksum matches baseline ($STORED_HASH)."
    else
        cat <<EOF >&2

============================================================
  WARNING: SPEEDY SCHEMA CHECKSUM CHANGED
============================================================
  Stored baseline:  $STORED_HASH
  Newly downloaded: $NEW_HASH

  The Speedy API schema content has changed since the last
  reviewed snapshot. This is normal when Speedy updates the API
  but it is also what an upstream tampering would look like.

  REVIEW the diff in bin/schemas/*.schema.json (git diff) carefully
  BEFORE running 'composer speedy:generate'. Once accepted, run:

    echo "$NEW_HASH" > $BASELINE_FILE

============================================================

EOF
    fi
fi
# -----------------------------------------------------------------------------

echo "Snapshotted $(ls bin/schemas/*.schema.json | wc -l | tr -d ' ') schemas to bin/schemas/"
