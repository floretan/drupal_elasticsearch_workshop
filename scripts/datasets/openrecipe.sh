# Download our sample dataset
if [ ! -f "datasets/recipes.json" ]; then
  mkdir datasets
  wget http://openrecipes.s3.amazonaws.com/recipeitems-latest.json.gz
  gunzip recipeitems-latest.json.gz
  mv recipeitems-latest.json datasets/recipes.json

  echo "The recipes database is located at datasets/recipes.json".
fi
