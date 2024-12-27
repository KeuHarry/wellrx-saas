# Build Arguments
ARG IMAGE_VERSION=node:22
ARG NODE_ENV=production

# Stage 1: build stage
FROM ${IMAGE_VERSION} as build-stage

# Set environment variables
ENV NODE_ENV=$NODE_ENV

WORKDIR /home/node/app

# Copy package files first for better caching
COPY --link --chown=1000:1000 ./src/ ./
COPY --link --chown=1000:1000 ./package.json ./yarn.lock ./
COPY --link --chown=1000:1000 ./index.html ./vite.config.ts ./tsconfig.app.json ./tsconfig.json ./tsconfig.node.json ./.prettierrc.json ./eslint.config.js ./

RUN yarn install && yarn build

# Stage 2: Serve the built app
FROM nginx:stable-alpine as production-stage

COPY --chown=1000:1000 --from=build-stage /home/node/app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
