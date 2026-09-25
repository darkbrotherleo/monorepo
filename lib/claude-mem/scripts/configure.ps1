# configure.ps1 - Claude-Mem Configuration Script

# This script helps configure Claude-Mem for use with agents.
# It assumes Claude-Mem has already been installed.

# --- Configuration Variables ---

# Set the desired worker port. Default is 8787.
$workerPort = 8787

# Set the memory provider. Options: 'cmem-pro' (default, hosted), 'host' (local observer)
# Other providers might be available based on user keys (OpenRouter, Gemini, Anthropic).
$memoryProvider = "host" # Defaulting to local observer for agent integration

# Set the default mode for the agent (e.or. 'code', 'code--zh').
# Refer to lib/claude-mem/AGENT.md for available modes.
$agentMode = "code"

# --- Script Execution ---

Write-Host "Configuring Claude-Mem..."

# Check if Claude-Mem is installed (basic check)
if (-not (Get-Command npx -ErrorAction SilentlyContinue)) {
    Write-Error "npx is required but not found. Please ensure Node.js is installed and in your PATH."
    exit 1
}

# Instructions for starting the worker (user needs to run this manually or via another script)
Write-Host "Worker Service:"
Write-Host "  To start the Claude-Mem worker, run the following command in your terminal:"
Write-Host "  claude-mem worker --port $workerPort"

# Attempt to configure settings (if applicable via CLI or file)
# Note: Claude-Mem primarily uses environment variables or a settings file.
# This script will guide the user on manual configuration steps.

Write-Host "Configuration Steps:"
Write-Host "1. Set Environment Variables (Recommended):
   - `$env:CLAUDE_MEM_PROVIDER = '$memoryProvider'`
   - `$env:CLAUDE_MEM_MODE = '$agentMode'`
   - `$env:CLAUDE_MEM_PORT = '$workerPort'`
   (These variables are session-specific. For permanent settings, consider user profile or startup scripts.)

2. Or, edit the settings file manually:
   - File path: `$HOME\.claude-mem\settings.json`
   - Example content:
     {
       "provider": "$memoryProvider",
       "port": $workerPort,
       "mode": "$agentMode"
     }

   (Ensure the directory `$HOME\.claude-mem` exists.)"

# Inform user about the next steps
Write-Host "" # Blank line
Write-Host "Configuration guidance provided. Please follow the steps above to configure Claude-Mem for your environment."
Write-Host "Refer to lib/claude-mem/AGENT.md for agent-specific usage instructions."
