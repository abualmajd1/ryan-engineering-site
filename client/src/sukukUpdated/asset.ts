import { ryanAsset } from '../runtime';

export const sukukUpdatedAsset = (path: string) => ryanAsset(`sukuk-updated/${path.replace(/^\//, '')}`);
