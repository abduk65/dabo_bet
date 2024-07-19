import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import { fileURLToPath } from 'url';
import { dirname } from 'path';
import TerserPlugin from 'terser-webpack-plugin';
import glob from 'glob-all';
import fs from 'fs';

// Resolve __dirname equivalent
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Function to get all existing JS files
const getAllJsFiles = () => {
    return glob.sync(path.resolve(__dirname, 'public/js/**/*.js'));
};

// Function to remove unused files
const cleanupUnusedFiles = () => {
    const jsFiles = getAllJsFiles();
    const usedFiles = jsFiles.map(file => path.relative(__dirname, file));

    // Remove all files from the output directory that are not used
    const outputDir = path.resolve(__dirname, 'public/dist/js');
    fs.readdirSync(outputDir).forEach(file => {
        if (!usedFiles.includes(file)) {
            fs.unlinkSync(path.join(outputDir, file));
        }
    });
};

export default {
    mode: 'production', // Set to 'development' for development mode
    entry: {
        // Dynamic entry points based on existing files
        ...Object.fromEntries(
            getAllJsFiles().map(file => [path.basename(file, '.js'), file])
        )
    },
    output: {
        filename: 'js/[name].bundle.js',
        path: path.resolve(__dirname, 'public/dist'),
        clean: true, // Clean the output directory before each build
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader'
                ],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/[name].bundle.css',
        }),
        // Custom plugin to clean up unused JS files
        {
            apply: (compiler) => {
                compiler.hooks.done.tap('CleanupUnusedFilesPlugin', cleanupUnusedFiles);
            }
        }
    ],
    optimization: {
        minimizer: [
            new TerserPlugin({
                extractComments: false,
            }),
        ],
    },
};
