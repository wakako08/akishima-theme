#!/usr/bin/env bash
# Deploy theme to sakura via SFTP (run on your Mac after git push).
# Usage:
#   ./scripts/deploy-to-sakura.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

HOST="${SFTP_HOST:-akishimajichiren.sakura.ne.jp}"
USER="${SFTP_USERNAME:-akishimajichiren}"
REMOTE="${SFTP_REMOTE_PATH:-/home/akishimajichiren/www/wp3/wp-content/themes/akishima}"
PASS="${SFTP_PASSWORD:-}"

if [[ -z "$PASS" && -f "$ROOT/.vscode/sftp.json" ]]; then
  PASS="$(python3 - <<'PY'
import json
from pathlib import Path
print(json.loads(Path(".vscode/sftp.json").read_text())["password"])
PY
)"
fi

if [[ -z "$PASS" ]]; then
  echo "Set SFTP_PASSWORD or keep .vscode/sftp.json locally."
  exit 1
fi

if ! command -v lftp >/dev/null 2>&1; then
  echo "lftp is required. Install with: brew install lftp"
  exit 1
fi

REMOTE="${REMOTE%/}"
echo "Deploying to ${USER}@${HOST}:${REMOTE}"

SCRIPT="$(mktemp)"
cat > "$SCRIPT" <<EOF
set sftp:auto-confirm yes
set net:timeout 30
set cmd:fail-exit yes
open sftp://${HOST}
user ${USER} "${PASS}"
cd ${REMOTE}
mirror -R --verbose --parallel=2 \
  --exclude ^\\.git/ \
  --exclude ^\\.github/ \
  --exclude ^\\.vscode/ \
  --exclude ^\\.venv-map/ \
  --exclude ^node_modules/ \
  --exclude ^scripts/ \
  --exclude ^assets/images/wp3/ \
  --exclude ^assets/videos/ \
  --exclude-glob .DS_Store \
  ./ .
bye
EOF

lftp -f "$SCRIPT"
rm -f "$SCRIPT"
echo "Deploy finished."
