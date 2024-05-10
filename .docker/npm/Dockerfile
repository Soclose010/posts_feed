# Your node version
FROM node:20-alpine

WORKDIR /var/www
ADD . /var/www

RUN npm install

EXPOSE 5252

ENTRYPOINT ["npm", "run", "dev"]
