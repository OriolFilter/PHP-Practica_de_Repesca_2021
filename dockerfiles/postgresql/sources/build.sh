#!/bin/bash

##########################
# @Oriol Filter Anson    #
# @13/05/2021            #
##########################

#########################################
# Script intended for dockerfiles usage #
#########################################


##############
# COLOR VARS #
##############

COLOR_DEFAULT='\e[39m'
COLOR_RED='\e[91m'
COLOR_GREEN='\e[92m'
COLOR_BLUE='\e[34m'
COLOR_YELLOW='\e[93m'

###############
#     ENV     #
###############

WORKDIR="$(pwd)"

declare -A FORMAT_ARR=(["SKEL"]='_skel' ["USERS"]='_users' ["VALUES"]='_val')
export PGPASSWORD="$POSTGRES_PASSWORD"

###############
#    VARS     #
###############

declare -a DATABASE_ARR=('institut')

########
# MAIN #
########

printf "${COLOR_YELLOW}[!INFO] [$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}INITIATING DATABASE CREATION${COLOR_DEFAULT}\n"

# Create a database for every entry in $DATABASE_ARR

for database in "${DATABASE_ARR[@]}"
do
  printf "${COLOR_YELLOW}[!INFO]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}CREATING DATABASE ${database}${COLOR_DEFAULT}\n"
  if psql -U "$POSTGRES_USER" -d postgres -c "CREATE DATABASE $database" > /dev/null; then
    printf "${COLOR_GREEN}[!SUCCESS]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}SUCCEFULLY CREATED DATABASE ${database}${COLOR_DEFAULT}\n"

    # Formatting database:
    # Execute every file that can find using the keys in $FORMAT_ARR it it's own database
    # Ej: ${WORKDIR}/${database}${key}.sql -> /sources/shop_skel.sql

    for key in "${!FORMAT_ARR[@]}"
    do
      sqlfile="${WORKDIR}/${database}${FORMAT_ARR[$key]}.sql"
      if [ -f "${sqlfile}" ]; then
        printf "${COLOR_YELLOW}[!INFO]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}FOUND SQL FILE!: ${sqlfile}${COLOR_DEFAULT}\n"
        printf "${COLOR_YELLOW}[!INFO]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}Proceeding to execute: ${sqlfile}${COLOR_DEFAULT}\n"
        if psql -U "$POSTGRES_USER" -d $database -f "${sqlfile}" > /dev/null; then
          printf "${COLOR_GREEN}[!SUCCESS]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}FINISHED EXECUTING SQL FILE: ${sqlfile}${COLOR_DEFAULT}\n"
        else
          printf "${COLOR_RED}[!ERROR]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}COULDN'T FINISHED EXECUTING SQL FILE: ${sqlfile}${COLOR_DEFAULT}\n"
        fi

      else
        printf "${COLOR_RED}[!ERROR]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}Couldn'f find file: ${sqlfile}${COLOR_DEFAULT}\n"
      fi
      sqlfile=''
    done
  else
    printf "${COLOR_RED}[!ERROR]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}ERROR CREATING DATABASE: ${database}${COLOR_DEFAULT}\n"
  fi
  database=''
done
# Good Ending
printf "${COLOR_GREEN}[!SUCCESS]${COLOR_DEFAULT} ${COLOR_YELLOW}[$(date +%H:%m:%S)]${COLOR_DEFAULT} ${COLOR_BLUE}Finished postgresql initialitzation${COLOR_DEFAULT}\n"