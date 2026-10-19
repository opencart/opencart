export const raw = (value) => String.raw`${value}`;
export const css = (value) => String.raw`${value}`;
export const safe = (value) => String(value ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);
export const html = (strings, ...values) => safe(strings.reduce((out, s, i) => out + s + (i < values.length ? safe(values[i]) : ''), ''));
export const escape = (value) => String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');