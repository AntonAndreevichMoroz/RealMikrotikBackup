docker exec rmb-n8n n8n export:workflow --all --output=/home/node/data-export/workflow.json
docker exec rmb-n8n n8n export:credentials --all --decrypted --output=/home/node/data-export/credentials.json
