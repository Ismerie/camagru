FROM nginx:alpine

# Copie les fichiers frontend dans Nginx
COPY ./ /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]