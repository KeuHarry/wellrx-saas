export const getEnv = (name: string): string => {
  return import.meta.env[`VITE_${name}`] as string;
};
