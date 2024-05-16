# Your node version
FROM node:20-alpine

WORKDIR /var/www
ADD package.json /var/www

RUN npm install --save-dev

ENTRYPOINT ["npm", "run", "dev"]
