import { registry } from './library/registry.js';
import { factory } from './library/factory.js';
import { loader } from './library/loader.js';

// Config
loader.library('config');

// DB
loader.library('db');

// Language
loader.library('language');

// Local
loader.library('local');

// Request
loader.library('request');

// Session
loader.library('session');

// Storage
loader.library('storage');

// Template
loader.library('template');

export { registry, factory, loader };