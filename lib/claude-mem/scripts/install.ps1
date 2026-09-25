# install.ps1 - Claude-Mem Installation Script

# This script automates the installation of Claude-Mem.

# Prerequisites:
# - Node.js and npm must be installed and added to the system's PATH.
#   Download from: https://nodejs.org/
# - Bun may be auto-installed by Claude-Mem if needed.

Write-Host "Starting Claude-Mem installation..."

# Check for Node.js and npm
if (-not (Get-Command node -ErrorAction SilentlyContinue) -or -not (Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Error "Node.js and npm are required but not found. Please install them from https://nodejs.org/ and restart your terminal."
    exit 1
}

Write-Host "Node.js and npm found."

# Installation command using npx (recommended by Claude-Mem docs)
# This command installs the CLI and worker service.
try {
    Write-Host "Running: npx claude-mem install"
    npx claude-mem install

    if ($LASTEXITCODE -ne 0) {
        Write-Error "npx claude-mem install failed. Please check the output above for details."
        exit 1
    }

    Write-Host "Claude-Mem installation completed successfully."

} catch {
    Write-Error "An error occurred during installation: $($_.Exception.Message)"
    exit 1
}

# Optional: Provide instructions for next steps
Write-Host "" # Blank line for spacing
Write-Host "Next steps: "
Write-Host "1. You may need to restart your terminal or IDE."
Write-Host "2. Run 'claude-mem worker --port 8787' to start the worker service."
Write-Host "3. Refer to lib/claude-mem/AGENT.md and lib/claude-mem/README.md for usage instructions."
