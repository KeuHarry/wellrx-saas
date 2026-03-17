FROM node:22

WORKDIR /home/node/app

# Copy package files first for better caching
# COPY --link --chown=1000:1000 ./src/ ./
COPY ./package.json ./yarn.lock ./
# COPY --link --chown=1000:1000 ./index.html ./vite.config.ts ./tsconfig.app.json ./tsconfig.json ./tsconfig.node.json ./.prettierrc.json ./eslint.config.js ./

RUN yarn install

COPY . .

RUN yarn build
