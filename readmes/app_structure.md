# Application Structure
All the application code, our javascript and vue components, in a subfolder called src.

**/src:**

- `app.js` : The application entrypoint.
- `App.vue`: The root component
- `pages`: A folder containing all top-level components, each of these will have a
route entry associated with it.
- `components`: A folder containing our building block components. Components will
be organised into sub-folders based on feature.
- `router`: A folder for all our vue-router configuration.
- `static`: Contain theme assets that doesn't need to be processed by Webpack.

**TBU**

## Build file (./build/webpack.config.dev.js)
The module section will contain all our loaders, each loader declaration consists
of a minimum of 2 properties, test and loader. Test is a regular expression that
webpack will use to identify which file types to be processed by this loader and
loader is the name of the loader itself.

## NPM Packages:
**babel preset env**
- For compiling Javascript ES6 code down to ES5

**vue-style-loader**
- Get CSS from a Vue file or any JavaScript files(css-loader) and inject it into my HTML as a style tag (vue-style-loader).

**copy-webpack-plugin**
- For copying static assets like images and video, they will not be processed by
Webpack but require in our dist directory.

**sass-loader**
- Compiles Sass to CSS, using Node Sass by default
- This plugin depends on another loader called `node-sass` so we have installed both.

**TBA**

## Composer Packages:
**TBA**
