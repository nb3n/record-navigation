import Logo from '@/assets/logo/logo';
import { Separator } from '@/components/ui/separator';

type FooterData = {
    title: string;
    links: {
        title: string;
        href: string;
    }[];
};

const footerSections: FooterData[] = [
    {
        title: 'Product',
        links: [
            {
                title: 'Plugin',
                href: 'https://filamentphp.com/plugins/nben-malla-record-navigation',
            },
            {
                title: 'Documentation',
                href: 'https://filamentphp.com/plugins/nben-malla-record-navigation#documentation',
            },
            {
                title: 'Get Started',
                href: 'https://filamentphp.com/plugins/nben-malla-record-navigation#installation',
            },
        ],
    },
    {
        title: 'Community',
        links: [
            {
                title: 'GitHub Repository',
                href: 'https://github.com/nb3n/filament-record-nav',
            },
            {
                title: 'Report Issues',
                href: 'https://github.com/nb3n/filament-record-nav/issues',
            },
            {
                title: 'Pull Requests',
                href: 'https://github.com/nb3n/filament-record-nav/pulls',
            },
            {
                title: 'Discord Support',
                href: 'https://discord.com/channels/883083792112300104/1385332641779417118',
            },
            {
                title: 'Sponsor',
                href: 'https://github.com/sponsors/nb3n',
            },
        ],
    },
];

const Footer = () => {
    return (
        <footer className="py-10">
            <div className="mx-auto max-w-7xl px-4 lg:px-8 xl:px-16">
                <div className="flex flex-col gap-6 sm:gap-12">
                    <div className="grid grid-cols-2 gap-x-8 gap-y-10 px-6 py-12 sm:grid-cols-4 md:grid-cols-7 lg:grid-cols-12 xl:px-0">
                        <div className="col-span-full lg:col-span-4">
                            <div className="flex animate-in flex-col gap-6 delay-100 duration-1000 ease-in-out fill-mode-both slide-in-from-bottom-10 fade-in">
                                <a href="/">
                                    <Logo />
                                </a>

                                <p className="text-base font-normal text-muted-foreground">
                                   A Laravel package that adds smooth next/previous 
                                   record navigation to Filament admin panels with 
                                   intuitive controls.
                                </p>
                            </div>
                        </div>

                        <div className="col-span-2 hidden lg:block"></div>

                        {footerSections.map(({ title, links }, index) => (
                            <div key={index} className="col-span-3">
                                <div className="flex animate-in flex-col gap-4 delay-100 duration-1000 ease-in-out fill-mode-both slide-in-from-bottom-10 fade-in">
                                    <p className="text-base font-medium text-foreground">
                                        {title}
                                    </p>
                                    <ul className="flex flex-col gap-3">
                                        {links.map(({ title, href }) => (
                                            <li key={title}>
                                                <a
                                                    href={href}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="text-base font-normal text-muted-foreground hover:text-foreground"
                                                >
                                                    {title}
                                                </a>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        ))}
                    </div>
                    <Separator orientation="horizontal" />
                    <div className="animate-in flex items-center justify-between text-sm font-normal text-muted-foreground delay-100 duration-1000 ease-in-out fill-mode-both slide-in-from-bottom-10 fade-in">
                        <p>© {new Date().getFullYear()} Record Navigation. All Rights Reserved</p>

                        <a 
                            href="https://nben.com.np"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            NBEN
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    );
};

export default Footer;
