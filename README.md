1. Criar um banco de dados
2. docker image build -f docker/Dockerfile -t banco-concorrencia . && docker run -d --name=banco-concorrencia -p 8000:80 banco-concorrencia && php artisan migrate:fresh --seed
