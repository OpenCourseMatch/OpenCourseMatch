#!/bin/sh

# Detect the operating system and architecture
os=$(uname -s)
arch=$(uname -m)

binary=""

# Choose the correct binary file
if [ "$os" = "Linux" ]; then
    if [ "$arch" = "x86_64" ]; then
        binary="tailwindcss-linux-x64"
    elif [ "$arch" = "aarch64" ]; then
        binary="tailwindcss-linux-arm64"
    elif [ "$arch" = "armv7l" ]; then
        binary="tailwindcss-linux-armv7"
    fi
elif [ "$os" = "Darwin" ]; then # macOS
    if [ "$arch" = "x86_64" ]; then
        binary="tailwindcss-macos-x64"
    elif [ "$arch" = "arm64" ]; then
        binary="tailwindcss-macos-arm64"
    fi
elif echo "$os" | grep -q "CYGWIN\|MINGW\|MSYS"; then
    if [ "$arch" = "x86_64" ]; then
        binary="tailwindcss-windows-x64.exe"
    elif [ "$arch" = "aarch64" ]; then
        binary="tailwindcss-windows-arm64.exe"
    fi
fi

if [ "$binary" = "" ]; then
    echo "Unsupported OS or architecture: $os $arch"
    exit 1
fi

# Run the binary
"framework/src/lib/tailwindcss/bin/$binary" "$@"
result=$?

exit $result
