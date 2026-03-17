import { getEnv } from './getEnv';

export const CENTRAL_URL = getEnv('APP_API_URL') + '/v1';
export const API_URL = getEnv('APP_API_URL');
