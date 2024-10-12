# Use a imagem Node.js oficial como base
FROM node:16

# Defina o diretório de trabalho dentro do container
WORKDIR /app

# Copie o arquivo package.json e package-lock.json
COPY package.json ./

# Instale as dependências do Angular
RUN npm install -g @angular/cli
RUN npm install

# Copie o restante dos arquivos do projeto
COPY . .

# Exponha a porta onde o servidor Angular irá rodar
EXPOSE 4200

# Inicie o servidor Angular
CMD ["npm", "start"]
