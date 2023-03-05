FROM nginx

#ENV UNAME=nginx
WORKDIR /runtime


COPY ./docker/nginx/site.conf /etc/nginx/conf.d/default.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log \
 && ln -sf /usr/sbin/cron /usr/sbin/crond

#RUN apk add --update --no-cache vim curl 

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]