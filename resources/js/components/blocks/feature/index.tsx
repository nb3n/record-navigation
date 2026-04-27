'use client';
import type { LucideIcon } from 'lucide-react';
import { Zap, Settings2, Route, Asterisk } from 'lucide-react';
import { motion } from 'motion/react';
import { Badge } from '@/components/ui/badge';
import { buttonVariants } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Kbd } from '@/components/ui/kbd';
import { cn } from '@/lib/utils';

type Feature = {
    icon: LucideIcon;
    title: string;
    content: string | React.ReactNode;
};

const featureData: Feature[] = [
    {
        icon: Zap,
        title: 'Zero configuration, instant navigation.',
        content: (
            <>
                Drop two actions into <Kbd>getHeaderActions()</Kbd> and you are
                done. No trait required, no setup and the actions work out of
                the box with a sensible config-driven default.
            </>
        ),
    },
    {
        icon: Route,
        title: 'Navigate to any page type.',
        content: (
            <>
                Target the view page, edit page, or any custom route registered
                in your resource. Pass <Kbd>NavigationPage::Edit</Kbd> or{' '}
                <Kbd>NavigationPage::custom()</Kbd> to <Kbd>navigateTo()</Kbd>{' '}
                on either action independently.
            </>
        ),
    },
    {
        icon: Settings2,
        title: 'Fully overridable query logic.',
        content: (
            <>
                Add the optional <Kbd>WithRecordNavigation</Kbd> trait and
                override <Kbd>getPreviousRecord()</Kbd> or{' '}
                <Kbd>getNextRecord()</Kbd> to filter by status, scope to a
                tenant, or order by a custom column.
            </>
        ),
    },
];

const Feature = () => {
    return (
        <section>
            <div className="py-8 sm:py-16 lg:py-20">
                <div className="mx-auto max-w-6xl px-4 sm:px-8">
                    <div className="flex flex-col gap-8 md:gap-16">
                        <motion.div
                            initial={{ y: -10, opacity: 0 }}
                            whileInView={{ y: 0, opacity: 1 }}
                            viewport={{ once: true }}
                            transition={{
                                duration: 0.8,
                                ease: [0.21, 0.47, 0.32, 0.98],
                            }}
                            className="mx-auto flex max-w-lg flex-col items-center justify-center gap-4"
                        >
                            <Badge
                                variant={'outline'}
                                className="h-auto px-3 py-1 text-sm"
                            >
                                Features
                            </Badge>
                            <h2 className="text-center text-3xl font-semibold tracking-[-1px] md:text-4xl">
                                Powerful by default, flexible when you need it
                            </h2>
                        </motion.div>
                        <motion.div
                            variants={{
                                hidden: { opacity: 0 },
                                show: {
                                    opacity: 1,
                                    transition: {
                                        staggerChildren: 0.1,
                                    },
                                },
                            }}
                            initial="hidden"
                            whileInView="show"
                            viewport={{ once: true }}
                            className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            {featureData.map((feature, index) => {
                                return (
                                    <motion.div
                                        key={index}
                                        variants={{
                                            hidden: {
                                                opacity: 0,
                                                y: 30,
                                                filter: 'blur(4px)',
                                            },
                                            show: {
                                                opacity: 1,
                                                y: 0,
                                                filter: 'blur(0px)',
                                            },
                                        }}
                                        transition={{
                                            duration: 0.8,
                                            ease: [0.21, 0.47, 0.32, 0.98],
                                        }}
                                    >
                                        <Card className="h-full border-t-4 border-t-transparent py-10 transition-all duration-300 hover:border-t-primary hover:shadow-lg">
                                            <CardContent className="flex flex-col gap-6 px-8">
                                                <feature.icon
                                                    className="h-8 w-8 text-primary"
                                                    strokeWidth={1.2}
                                                />
                                                <div className="flex flex-col gap-3">
                                                    <h6 className="text-xl font-semibold">
                                                        {feature.title}
                                                    </h6>
                                                    <p className="text-base font-normal text-muted-foreground">
                                                        {feature.content}
                                                    </p>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    </motion.div>
                                );
                            })}
                        </motion.div>
                        <motion.div
                            initial={{ y: 20, opacity: 0 }}
                            whileInView={{ y: 0, opacity: 1 }}
                            viewport={{ once: true }}
                            transition={{
                                duration: 0.8,
                                ease: [0.21, 0.47, 0.32, 0.98],
                            }}
                            className="flex flex-col items-center justify-center gap-5"
                        >
                            <div className="flex items-center gap-2 text-muted-foreground">
                                <Asterisk size={16} />
                                <p className="text-sm font-normal">
                                    Have questions? Join the discussion on
                                    Discord
                                </p>
                            </div>
                            <a
                                className={cn(
                                    buttonVariants({ variant: 'default' }),
                                    'h-full cursor-pointer rounded-full px-5 py-2.5 shadow-xs',
                                )}
                                href="https://discord.com/channels/883083792112300104/1385332641779417118"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Join on Discord
                            </a>
                        </motion.div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default Feature;
