FROM nginx:alpine

RUN apk update && apk upgrade && \
		apk add --no-cache bash

RUN rm /etc/nginx/conf.d/default.conf
COPY ./containers/nginx/nginx.conf.template /etc/nginx

