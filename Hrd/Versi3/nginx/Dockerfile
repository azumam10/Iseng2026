FROM nginx:1.25-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash tzdata curl

# Set timezone properly
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Remove default nginx config
RUN rm -f /etc/nginx/conf.d/default.conf

# Security: hide nginx version
RUN sed -i 's/# server_tokens off;/server_tokens off;/' /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]