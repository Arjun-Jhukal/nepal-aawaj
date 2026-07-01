# Project Setup

This project uses **Node.js v22.16.0** and includes a build process using `npm`.

## 🛠 Requirements

- Node.js `v22.16.0`
- npm (comes with Node.js)

## 📁 Installation Steps

1. Clone the repository and navigate to the `src` directory:

```bash
cd src
npm install
npm run dev
```

## 🚀 GitHub deployment

This theme can be deployed automatically from GitHub using the workflow in [.github/workflows/deploy.yml](.github/workflows/deploy.yml).

### What the workflow does

1. Checks out the latest code from GitHub.
2. Installs Node.js and the required packages from [src/package.json](src/package.json).
3. Runs the build step, which compiles SCSS into CSS and minifies JavaScript.
4. Uploads the finished theme folder to your web server.

### Required GitHub secrets

Add these secrets in your GitHub repository settings:

- SSH_HOST
- SSH_USER
- SSH_PRIVATE_KEY
- SSH_PORT (optional, defaults to 22)

### Server path

The deployment target is:

- /public_html/wp-content/themes/nepal-aawaj

### Next time you deploy

1. Commit your changes.
2. Push to the main branch.
3. GitHub Actions will run the workflow automatically.
4. If needed, trigger it manually from the Actions tab.

### Important note

Always change the source SCSS and JavaScript files, then let the build step generate the compiled assets before deployment.
