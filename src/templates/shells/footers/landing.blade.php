<div class="p-4 bg-surface-100 text-surface-700">
    <footer class="max-w-screen-xl m-auto">
        <div class="flex justify-start items-center">
            <img src="{{ Router->staticFilePath("img/logo.svg") }}"
                 alt="Logo"
                 class="h-8 w-auto rounded"
            >
            <span class="hidden sm:block ml-2 font-bold">
                {{ Config->getAppName() }}
            </span>
        </div>

        <div class="flex flex-wrap justify-between gap-2 mt-2 text-sm">
            <div class="flex flex-col text-sm">
                <p>
                    Using
                    <span class="inline-flex">
                        <a href="{{ Router->generate("dependencies-overview") }}"
                           class="text-primary-500 hover:text-primary-600 transition-all">
                            these software dependencies
                        </a>.
                    </span>
                </p>
                <p>
                    Licensed under the
                    <span class="inline-flex">
                        <a href="https://github.com/OpenCourseMatch/OpenCourseMatch/blob/main/LICENSE"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-primary-500 hover:text-primary-600 transition-all">
                            MIT License
                        </a>.
                    </span>
                </p>
                <p>
                    Built with ❤️ and lack of 🛏️ by
                    <span class="inline-flex">
                        <a href="https://github.com/OpenCourseMatch/OpenCourseMatch/graphs/contributors"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-primary-500 hover:text-primary-600 transition-all">
                            OpenCourseMatch contributors
                        </a>.
                    </span>
                </p>
            </div>

            <span class="inline-flex items-center gap-1 inline-block float-right text-xs">
                <svg class="fill-current animate-spin h-3"
                     viewBox="0 0 356 356"
                     xmlns="http://www.w3.org/2000/svg"
                     style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                    <g transform="matrix(1.35465,0,0,1.37959,-67.5268,-66.0084)">
                        <path d="M193.775,48.45C185.334,47.645 176.833,47.645 168.391,48.45L164.21,68.436C156.119,69.652 148.189,71.738 140.563,74.658L126.765,59.402C119.044,62.849 111.682,67.023 104.781,71.865L111.337,91.226C104.949,96.251 99.144,101.952 94.026,108.224L74.309,101.787C69.378,108.562 65.127,115.792 61.617,123.373L77.153,136.921C74.18,144.409 72.055,152.196 70.817,160.141L50.463,164.246C49.643,172.535 49.643,180.883 50.463,189.172L70.817,193.277C72.055,201.222 74.18,209.009 77.153,216.497L61.617,230.045C65.127,237.627 69.378,244.856 74.309,251.632L94.026,245.194C99.144,251.467 104.949,257.167 111.337,262.192L104.781,281.553C111.682,286.395 119.044,290.569 126.765,294.016L140.563,278.76C148.189,281.68 156.119,283.766 164.21,284.982L168.391,304.968C176.833,305.773 185.334,305.773 193.775,304.968L197.956,284.982C206.047,283.766 213.978,281.68 221.604,278.76L235.401,294.016C243.122,290.569 250.484,286.395 257.385,281.553L250.829,262.192C257.217,257.167 263.022,251.467 268.14,245.194L287.857,251.632C292.788,244.856 297.039,237.627 300.549,230.045L285.013,216.497C287.986,209.009 290.111,201.222 291.349,193.277L311.703,189.172C312.523,180.883 312.523,172.535 311.703,164.246L291.349,160.141C290.111,152.196 287.986,144.409 285.013,136.921L300.549,123.373C297.039,115.792 292.788,108.562 287.857,101.787L268.14,108.224C263.022,101.952 257.217,96.251 250.829,91.226L257.385,71.865C250.484,67.023 243.122,62.849 235.401,59.402L221.604,74.658C213.978,71.738 206.047,69.652 197.956,68.436L193.775,48.45ZM181.083,150.937C195.569,150.937 207.33,162.485 207.33,176.709C207.33,190.933 195.569,202.482 181.083,202.482C166.597,202.482 154.836,190.933 154.836,176.709C154.836,162.485 166.597,150.937 181.083,150.937Z" />
                    </g>
                </svg>
                {{ t("Version:") }} {{ ComposerReader->get("version") }}
            </span>
        </div>
    </footer>
</div>
