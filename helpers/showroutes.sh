#!/bin/bash
php artisan route:list | grep "GET\|POST\|PUT\|PATCH\|DELTE" | sed -e 's/|HEAD//g' | sed -e 's/|//g' | awk '{print $1" "$2" "$3}' | column -t