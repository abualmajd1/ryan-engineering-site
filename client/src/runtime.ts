export const RYAN_ASSET_BASE =
  (typeof window !== 'undefined' && (window as Window & { RYAN_ASSET_BASE?: string }).RYAN_ASSET_BASE) ||
  import.meta.env.BASE_URL;

export const ryanAsset = (path: string) => `${RYAN_ASSET_BASE.replace(/\/+$/, '')}/${path.replace(/^\/+/, '')}`;
