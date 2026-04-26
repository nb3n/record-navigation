'use client';

import { ArrowUpRight } from 'lucide-react';
import { motion } from 'motion/react';
import { useState, useEffect, useCallback } from 'react';
import Logo from '@/assets/logo/logo';
import { buttonVariants } from '@/components/ui/button';
import { cn } from '@/lib/utils';

type HeaderProps = {
    className?: string;
};

const CollaborateButton = ({ className }: { className?: string }) => (
    <a
        href="https://rnd.nben.com.np/admin"
        className={cn(
            buttonVariants({ variant: 'default' }),
            'group relative h-10 w-fit overflow-hidden rounded-full p-1 ps-4 pe-12 text-sm font-medium transition-all duration-500 hover:ps-12 hover:pe-4',
            className,
            'cursor-pointer',
        )}
    >
        <span className="relative z-10 transition-all duration-500">
            View Demo
        </span>
        <span className="absolute right-1 flex h-8 w-8 items-center justify-center rounded-full bg-background text-foreground transition-all duration-500 group-hover:right-[calc(100%-36px)] group-hover:rotate-45">
            <ArrowUpRight size={16} />
        </span>
    </a>
);

const Header = ({ className }: HeaderProps) => {
    const [sticky, setSticky] = useState(() =>
        typeof window !== 'undefined' ? window.scrollY >= 50 : false,
    );

    const handleScroll = useCallback(() => {
        setSticky(window.scrollY >= 50);
    }, []);

    useEffect(() => {
        window.addEventListener('scroll', handleScroll, { passive: true });

        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, [handleScroll]);

    return (
        <motion.header
            initial={{ opacity: 0, y: -32 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.7, ease: 'easeInOut' }}
            className={cn(
                'sticky inset-x-0 top-0 z-50 flex h-20 items-center justify-center px-4',
                className,
            )}
        >
            <div
                className={cn(
                    'flex h-fit w-full max-w-6xl items-center justify-between gap-3.5 transition-all duration-500 lg:gap-6',
                    sticky
                        ? 'rounded-full border border-border/40 bg-background/60 p-2.5 shadow-2xl shadow-primary/5 backdrop-blur-lg'
                        : 'border-transparent bg-transparent',
                )}
            >
                <div>
                    <a href="/">
                        <Logo className="gap-3" />
                    </a>
                </div>

                <div className="flex gap-4">
                    <CollaborateButton />
                </div>
            </div>
        </motion.header>
    );
};

export default Header;
