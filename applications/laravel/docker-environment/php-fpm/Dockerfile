#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#
# To edit the 'php-fpm' base Image, visit its repository on Github
#    https://github.com/Laradock/php-fpm
#
# To change its version, see the available Tags on the Docker Hub:
#    https://hub.docker.com/r/laradock/php-fpm/tags/
#
# Note: Base Image name format {image-tag}-{php-version}
#

ARG LARADOCK_PHP_VERSION
ARG BASE_IMAGE_TAG_PREFIX=latest
FROM laradock/php-fpm:${BASE_IMAGE_TAG_PREFIX}-${LARADOCK_PHP_VERSION}

LABEL maintainer="Mahmoud Zalt <mahmoud@zalt.me>"

ARG LARADOCK_PHP_VERSION

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      gnupg2 \
      git \
      #
      #--------------------------------------------------------------------------
      # Mandatory Software's Installation
      #--------------------------------------------------------------------------
      #
      # Mandatory Software's such as ("mcrypt", "pdo_mysql", "libssl-dev", ....)
      # are installed on the base image 'laradock/php-fpm' image. If you want
      # to add more Software's or remove existing one, you need to edit the
      # base image (https://github.com/Laradock/php-fpm).
      #
      # next lines are here becase there is no auto build on dockerhub see https://github.com/laradock/laradock/pull/1903#issuecomment-463142846
      libzip-dev zip unzip && \
      if [ ${LARADOCK_PHP_VERSION} = "7.3" ] || [ ${LARADOCK_PHP_VERSION} = "7.4" ] || [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        docker-php-ext-configure zip; \
      else \
        docker-php-ext-configure zip --with-libzip; \
      fi && \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'

#
#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------
#
# Optional Software's will only be installed if you set them to `true`
# in the `docker-compose.yml` before the build.
# Example:
#   - INSTALL_SOAP=true
#



###########################################################################
# libfaketime:
###########################################################################

USER root



###########################################################################
# pcntl
###########################################################################

ARG INSTALL_PCNTL=false
RUN if [ ${INSTALL_PCNTL} = true ]; then \
    # Installs pcntl, helpful for running Horizon
    docker-php-ext-install pcntl \
;fi

###########################################################################
# bcmath:
###########################################################################


###########################################################################
# Exif:
###########################################################################

ARG INSTALL_EXIF=false

RUN if [ ${INSTALL_EXIF} = true ]; then \
    # Enable Exif PHP extentions requirements
    docker-php-ext-install exif \
;fi


###########################################################################
# Opcache:
###########################################################################

ARG INSTALL_OPCACHE=false

RUN if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-install opcache \
;fi

# Copy opcache configration
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

###########################################################################
# Mysqli Modifications:
###########################################################################

ARG INSTALL_MYSQLI=false

RUN if [ ${INSTALL_MYSQLI} = true ]; then \
    docker-php-ext-install mysqli \
;fi


###########################################################################
# ImageMagick:
###########################################################################

USER root

ARG INSTALL_IMAGEMAGICK=false
ARG IMAGEMAGICK_VERSION=latest
ENV IMAGEMAGICK_VERSION ${IMAGEMAGICK_VERSION}

RUN if [ ${INSTALL_IMAGEMAGICK} = true ]; then \
    apt-get install -yqq libmagickwand-dev imagemagick && \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
      cd /tmp && \
      if [ ${IMAGEMAGICK_VERSION} = "latest" ]; then \
        git clone https://github.com/Imagick/imagick; \
      else \
        git clone --branch ${IMAGEMAGICK_VERSION} https://github.com/Imagick/imagick; \
      fi && \
      cd imagick && \
      phpize && \
      ./configure && \
      make && \
      make install && \
      rm -r /tmp/imagick; \
    else \
      pecl install imagick; \
    fi && \
    docker-php-ext-enable imagick; \
    php -m | grep -q 'imagick' \
;fi

###########################################################################
# SMB:
###########################################################################

ARG INSTALL_SMB=false

RUN if [ ${INSTALL_SMB} = true ]; then \
    apt-get install -yqq smbclient php-smbclient coreutils \
;fi

###########################################################################
# IMAP:
###########################################################################

ARG INSTALL_IMAP=false

RUN if [ ${INSTALL_IMAP} = true ]; then \
    apt-get install -yqq libc-client-dev libkrb5-dev && \
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install imap \
;fi

###########################################################################
# Calendar:
###########################################################################

USER root

ARG INSTALL_CALENDAR=false

RUN if [ ${INSTALL_CALENDAR} = true ]; then \
    docker-php-ext-configure calendar && \
    docker-php-ext-install calendar \
;fi

###########################################################################
# Phalcon:
###########################################################################

ARG INSTALL_PHALCON=false
ARG LARADOCK_PHALCON_VERSION
ENV LARADOCK_PHALCON_VERSION ${LARADOCK_PHALCON_VERSION}

# Copy phalcon configration
COPY ./phalcon.ini /usr/local/etc/php/conf.d/phalcon.ini.disable

RUN if [ $INSTALL_PHALCON = true ]; then \
    apt-get install -yqq unzip libpcre3-dev gcc make re2c git automake autoconf\
    && git clone https://github.com/jbboehr/php-psr.git \
    && cd php-psr \
    && phpize \
    && ./configure \
    && make \
    && make test \
    && make install \
    && curl -L -o /tmp/cphalcon.zip https://github.com/phalcon/cphalcon/archive/v${LARADOCK_PHALCON_VERSION}.zip \
    && unzip -d /tmp/ /tmp/cphalcon.zip \
    && cd /tmp/cphalcon-${LARADOCK_PHALCON_VERSION}/build \
    && ./install \
    && mv /usr/local/etc/php/conf.d/phalcon.ini.disable /usr/local/etc/php/conf.d/phalcon.ini \
    && rm -rf /tmp/cphalcon* \
;fi

###########################################################################
# APCU:
###########################################################################

ARG INSTALL_APCU=false

RUN if [ ${INSTALL_APCU} = true ]; then \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
        pecl install -a apcu-4.0.11; \
    else \
        pecl install apcu; \
    fi && \
    docker-php-ext-enable apcu \
;fi

###########################################################################
# YAML:
###########################################################################

USER root

ARG INSTALL_YAML=false

RUN if [ ${INSTALL_YAML} = true ]; then \
    apt-get install -yqq libyaml-dev; \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
        echo '' | pecl install -a yaml-1.3.2; \
    elif [ $(php -r "echo PHP_MAJOR_VERSION;") = "7" ] && [ $(php -r "echo PHP_MINOR_VERSION;") = "0" ]; then \
        echo '' | pecl install yaml-2.0.4; \
    else \
        echo '' | pecl install yaml; \
    fi \
    && docker-php-ext-enable yaml \
;fi

###########################################################################
# GETTEXT:
###########################################################################

ARG INSTALL_GETTEXT=false

RUN if [ ${INSTALL_GETTEXT} = true ]; then \
    apt-get install -yqq zlib1g-dev libicu-dev g++ libpq-dev libssl-dev gettext && \
    docker-php-ext-install gettext \
;fi

###########################################################################
# Install additional locales:
###########################################################################

ARG INSTALL_ADDITIONAL_LOCALES=false
ARG ADDITIONAL_LOCALES

RUN if [ ${INSTALL_ADDITIONAL_LOCALES} = true ]; then \
    apt-get install -yqq locales \
    && echo '' >> /usr/share/locale/locale.alias \
    && temp="${ADDITIONAL_LOCALES%\"}" \
    && temp="${temp#\"}" \
    && for i in ${temp}; do sed -i "/$i/s/^#//g" /etc/locale.gen; done \
    && locale-gen \
;fi

###########################################################################
# MySQL Client:
###########################################################################

USER root

ARG INSTALL_MYSQL_CLIENT=false

RUN if [ ${INSTALL_MYSQL_CLIENT} = true ]; then \
      apt-get -y install default-mysql-client \
;fi

###########################################################################
# ping:
###########################################################################

USER root

ARG INSTALL_PING=false

RUN if [ ${INSTALL_PING} = true ]; then \
    apt-get -y install inetutils-ping \
;fi

###########################################################################
# sshpass:
###########################################################################

USER root

ARG INSTALL_SSHPASS=false

RUN if [ ${INSTALL_SSHPASS} = true ]; then \
    apt-get -y install sshpass \
;fi

###########################################################################
# Docker Client:
###########################################################################

USER root

ARG INSTALL_DOCKER_CLIENT=false

RUN if [ ${INSTALL_DOCKER_CLIENT} = true ]; then \
    curl -sS https://download.docker.com/linux/static/stable/x86_64/docker-20.10.3.tgz -o /tmp/docker.tar.gz && \
    tar -xzf /tmp/docker.tar.gz -C /tmp/ && \
    cp /tmp/docker/docker* /usr/local/bin && \
    chmod +x /usr/local/bin/docker* \
;fi

###########################################################################
# FFMPEG:
###########################################################################

USER root

ARG INSTALL_FFMPEG=false

RUN if [ ${INSTALL_FFMPEG} = true ]; then \
    apt-get -y install ffmpeg \
;fi

###########################################################################
# BBC Audio Waveform Image Generator:
###########################################################################

USER root

ARG INSTALL_AUDIOWAVEFORM=false

RUN if [ ${INSTALL_AUDIOWAVEFORM} = true ]; then \
   apt-get -y install wget make cmake gcc g++ libmad0-dev libid3tag0-dev libsndfile1-dev libgd-dev libboost-filesystem-dev libboost-program-options-dev libboost-regex-dev \
   && git clone https://github.com/bbc/audiowaveform.git \
   && cd audiowaveform \
   && wget https://github.com/google/googletest/archive/release-1.10.0.tar.gz \
   && tar xzf release-1.10.0.tar.gz \
   && ln -s googletest-release-1.10.0/googletest googletest \
   && ln -s googletest-release-1.10.0/googlemock googlemock \
   && mkdir build \
   && cd build \
   && cmake .. \
   && make \
   && make install \
;fi


#####################################
# wkhtmltopdf:
#####################################

USER root

ARG INSTALL_WKHTMLTOPDF=false

RUN if [ ${INSTALL_WKHTMLTOPDF} = true ]; then \
    apt-get install -yqq \
      libxrender1 \
      libfontconfig1 \
      libx11-dev \
      libjpeg62 \
      libxtst6 \
      fontconfig \
      libjpeg62-turbo \
      xfonts-base \
      xfonts-75dpi \
      wget \
    && wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.stretch_amd64.deb \
    && dpkg -i wkhtmltox_0.12.6-1.stretch_amd64.deb \
    && apt -f install \
;fi

###########################################################################
# Mailparse extension:
###########################################################################

ARG INSTALL_MAILPARSE=false

RUN if [ ${INSTALL_MAILPARSE} = true ]; then \
    # Install mailparse extension
    printf "\n" | pecl install -o -f mailparse \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable mailparse \
;fi

###########################################################################
# CacheTool:
###########################################################################

ARG INSTALL_CACHETOOL=false

RUN if [ ${INSTALL_CACHETOOL} = true ]; then \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "7" ] && [ $(php -r "echo PHP_MINOR_VERSION;") -ge 1 ]; then \
            curl -sO http://gordalina.github.io/cachetool/downloads/cachetool.phar; \
    else \
        curl http://gordalina.github.io/cachetool/downloads/cachetool-3.2.1.phar -o cachetool.phar; \
    fi && \
    chmod +x cachetool.phar && \
    mv cachetool.phar /usr/local/bin/cachetool \
;fi

###########################################################################
# XMLRPC:
###########################################################################

ARG INSTALL_XMLRPC=false

RUN if [ ${INSTALL_XMLRPC} = true ]; then \
  apt-get -yq install libxml2-dev; \
  if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
    pecl install xmlrpc-1.0.0RC2; \
    docker-php-ext-enable xmlrpc; \
  else \
    docker-php-ext-install xmlrpc; \
  fi \
;fi

###########################################################################
# PHP DECIMAL:
###########################################################################

USER root

ARG INSTALL_PHPDECIMAL=false

RUN if [ ${INSTALL_PHPDECIMAL} = true ]; then \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
      echo 'decimal not support PHP 5.6'; \
    else \
      apt-get install -yqq libmpdec-dev \
      && pecl install decimal \
      && docker-php-ext-enable decimal \
      && php -m | grep -q 'decimal' \
    ;fi \
;fi

###########################################################################
# New Relic for PHP:
###########################################################################
ARG NEW_RELIC=${NEW_RELIC}
ARG NEW_RELIC_KEY=${NEW_RELIC_KEY}
ARG NEW_RELIC_APP_NAME=${NEW_RELIC_APP_NAME}

RUN if [ ${NEW_RELIC} = true ]; then \
  curl -L http://download.newrelic.com/php_agent/archive/9.9.0.260/newrelic-php5-9.9.0.260-linux.tar.gz | tar -C /tmp -zx && \
  export NR_INSTALL_USE_CP_NOT_LN=1 && \
  export NR_INSTALL_SILENT=1 && \
  /tmp/newrelic-php5-*/newrelic-install install && \
  rm -rf /tmp/newrelic-php5-* /tmp/nrinstall* && \
  sed -i \
  -e 's/"REPLACE_WITH_REAL_KEY"/"${NEW_RELIC_KEY}"/' \
  -e 's/newrelic.appname = "PHP Application"/newrelic.appname = "${NEW_RELIC_APP_NAME}"/' \
  -e 's/;newrelic.daemon.app_connect_timeout =.*/newrelic.daemon.app_connect_timeout=15s/' \
  -e 's/;newrelic.daemon.start_timeout =.*/newrelic.daemon.start_timeout=5s/' \
  /usr/local/etc/php/conf.d/newrelic.ini \
;fi
###########################################################################
# Downgrade Openssl:
###########################################################################

ARG DOWNGRADE_OPENSSL_TLS_AND_SECLEVEL=false

RUN if [ ${DOWNGRADE_OPENSSL_TLS_AND_SECLEVEL} = true ]; then \
    sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.2',g' /etc/ssl/openssl.cnf \
    && \
    sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf\
;fi

###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP ${LARADOCK_PHP_VERSION}."

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Adding the faketime library to the preload file needs to be done last
# otherwise it will preload it for all commands that follow in this file
RUN if [ ${INSTALL_FAKETIME} = true ]; then \
    echo "/usr/lib/x86_64-linux-gnu/faketime/libfaketime.so.1" > /etc/ld.so.preload \
;fi

# Configure locale.
ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
