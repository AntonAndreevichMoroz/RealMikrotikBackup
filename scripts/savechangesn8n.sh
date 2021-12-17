docker exec rmb-n8n n8n export:workflow --all --pretty --output=/home/node/data-export/workflow.json
docker exec rmb-n8n n8n export:credentials --all --decrypted --pretty --output=/home/node/data-export/credentials.json
