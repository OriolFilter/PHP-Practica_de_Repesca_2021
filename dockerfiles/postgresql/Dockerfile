FROM postgres:13-alpine
#RUN PROJECT_DATABASE='institut'
#RUN psql -U username -d postgres -c "CREATE DATABASE $PROJECT_DATABASE"
#ADD sources/db_default.sql /tmp/init_script.sql
ADD sources /sources
WORKDIR /sources
RUN ln -s $(pwd)/build.sh /docker-entrypoint-initdb.d/init_01.sh
#RUN pwd
#RUN ./build.sh
#ENTRYPOINT su postgres -c 'pg_ctl start -D /var/lib/postgresql/data -l serverlog'
#RUN ls  /var/lib/postgresql/data
#RUN echo -e ">>>>>  $PGDATA <<<<<<"
#RUN ls /docker-entrypoint-initdb.d/
#RUN echo -e ">>>>>  $PGDATA <<<<<<"
#RUN su - postgres -c "postgres -D $PGDATA"
#RUN psql objectrocket -h 127.0.0.1 -d institut -f test.sql