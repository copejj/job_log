#!/bin/bash

DOMAIN="left.braindribbler.com"

# Use nslookup to get the IP address
# The 'grep' command filters for lines containing "Address:"
# The 'awk' command extracts the second field (the IP address)
DOMAIN_IP=$(nslookup "$DOMAIN" | grep -E 'Address: [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+' | awk '{print $2}')

# Check if an IP address was found
if [ -z "$DOMAIN_IP" ]; then
    echo "Could not find the IP address for $DOMAIN."
fi

API_URL="https://api.ipify.org"
PUBLIC_IP=$(curl -s "$API_URL")
if [ $? -ne 0 ]; then
    echo "Error calling API $API_URL"
fi

if [ "$DOMAIN_IP" != "$PUBLIC_IP" ]; then
    echo "Domain IP ($DOMAIN_IP) does not match Public IP ($PUBLIC_IP), please update."
fi

