# Custom Nginx build with additional modules
FROM alpine:latest AS nginx-base

ARG NGINX_VERSION=1.29.1

WORKDIR /app

RUN apk add --no-cache pcre-dev zlib-dev openssl-dev wget git gcc make brotli-dev musl-dev && \
    # Download Nginx
    wget https://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz && \
    tar -zxf nginx-${NGINX_VERSION}.tar.gz && \
    rm nginx-${NGINX_VERSION}.tar.gz && \
    # Clone modules
    git clone --recurse-submodules -j8 https://github.com/google/ngx_brotli.git && \
    wget https://github.com/openresty/headers-more-nginx-module/archive/master.tar.gz -O headers-more.tar.gz && \
    mkdir -p headers-more-nginx-module && \
    tar -xzf headers-more.tar.gz -C headers-more-nginx-module --strip-components=1 && \
    rm headers-more.tar.gz && \
    # Compile modules
    cd nginx-${NGINX_VERSION} && \
    ./configure --with-compat \
        --with-cc-opt='-Os -fomit-frame-pointer' \
        --with-ld-opt='-Wl,--as-needed' \
        --add-dynamic-module=../ngx_brotli \
        --add-dynamic-module=../headers-more-nginx-module && \
    make -j$(nproc) modules && \
    # Create modules directory and copy only needed files
    mkdir -p /modules && \
    cp /app/nginx-${NGINX_VERSION}/objs/ngx_http_brotli_static_module.so \
       /app/nginx-${NGINX_VERSION}/objs/ngx_http_brotli_filter_module.so \
       /app/nginx-${NGINX_VERSION}/objs/ngx_http_headers_more_filter_module.so \
       /modules/ && \
    strip -s /modules/*.so && \
    # Clean temporary files
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Development stage
FROM nginx:alpine AS development

# Copy custom modules
COPY --from=nginx-base /modules/*.so /usr/lib/nginx/modules/

# Install development tools
RUN apk add --no-cache \
    curl \
    vim \
    nano

# Copy nginx configurations
COPY docker/nginx/conf/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf/default.conf /etc/nginx/conf.d/default.conf

# Create necessary directories
RUN mkdir -p /var/log/nginx /var/cache/nginx

# Set proper permissions
RUN chown -R nginx:nginx /var/log/nginx /var/cache/nginx

# Expose port
EXPOSE 80

# Production stage
FROM nginx:alpine AS production

# Copy custom modules
COPY --from=nginx-base /modules/*.so /usr/lib/nginx/modules/

# Copy nginx configurations
COPY docker/nginx/conf/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf/default.conf /etc/nginx/conf.d/default.conf

# Create necessary directories
RUN mkdir -p /var/log/nginx /var/cache/nginx

# Set proper permissions and cleanup
RUN chown -R nginx:nginx /var/log/nginx /var/cache/nginx && \
    rm -rf /var/cache/apk/* /tmp/*

# Expose port
EXPOSE 80
