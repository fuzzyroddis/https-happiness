find rules/ -type f -iname "*" -print0 | while IFS= read -r -d $'\0' line; do
    php validateRule.php "$line"   
done