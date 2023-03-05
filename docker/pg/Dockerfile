FROM postgres
USER postgres
RUN whoami
ADD ./docker/pg/init.sql /docker-entrypoint-initdb.d/init.sql
ENTRYPOINT ["docker-entrypoint.sh"]
EXPOSE 5432
CMD ["postgres"]