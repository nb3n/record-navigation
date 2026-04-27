'use client';
import type { LucideIcon } from 'lucide-react';
import { Target, WandSparkles, Zap, Plus } from 'lucide-react';
import { motion, useSpring, useTransform, useInView } from 'motion/react';
import { useEffect, useRef } from 'react';
import { cn } from '@/lib/utils';

type AboutusData = {
    icon: LucideIcon;
    title: string;
    color: string;
};

type StatisticsCounter = {
    title: string;
    count: number;
};

const aboutusData: AboutusData[] = [
    {
        icon: Zap,
        title: 'Zero Config',
        color: 'bg-blue-500/10 text-blue-500',
    },
    {
        icon: WandSparkles,
        title: 'Fully Flexible',
        color: 'bg-teal-400/10 text-teal-400',
    },
    {
        icon: Target,
        title: 'Query Optimised',
        color: 'bg-orange-400/10 text-orange-400',
    },
];

const statisticsCounter: StatisticsCounter[] = [
    {
        title: 'Database queries per action per render',
        count: 1,
    },
    {
        title: 'Lines to get started',
        count: 3,
    },
    {
        title: 'Filament versions supported',
        count: 2,
    },
];

const AnimatedCounter = ({
    value,
    isInView,
}: {
    value: number;
    isInView: boolean;
}) => {
    const springValue = useSpring(0, {
        bounce: 0,
        duration: 2000,
    });

    const displayValue = useTransform(springValue, (current) =>
        Math.round(current),
    );

    useEffect(() => {
        if (isInView) {
            springValue.set(value);
        }
    }, [isInView, value, springValue]);

    return <motion.span>{displayValue}</motion.span>;
};

const AboutUs = () => {
    const statsRef = useRef(null);
    const isInView = useInView(statsRef, { once: true, margin: '-100px' });

    return (
        <section className="py-8 sm:py-16 lg:py-20">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-16">
                <div className="flex flex-col items-center justify-center gap-8 md:gap-16">
                    <motion.div
                        initial={{ y: -40, opacity: 0 }}
                        whileInView={{ y: 0, opacity: 1 }}
                        viewport={{ once: true }}
                        transition={{
                            duration: 0.8,
                            ease: [0.21, 0.47, 0.32, 0.98],
                        }}
                        className="flex flex-col items-center justify-center gap-4"
                    >
                        <h2 className="text-center text-3xl font-medium tracking-tight text-foreground sm:text-4xl lg:text-5xl">
                            A Filament package built for speed, flexibility and
                            developer experience with
                        </h2>
                        <div className="flex flex-wrap items-center justify-center gap-x-2 gap-y-4">
                            {aboutusData.map((item, index) => (
                                <div
                                    key={index}
                                    className={cn(
                                        'flex items-center gap-3 rounded-full px-6 py-2',
                                        item.color,
                                    )}
                                >
                                    <item.icon className="h-6 w-6 sm:h-8 sm:w-8 lg:h-10 lg:w-10" />
                                    <span className="font-instrument text-4xl font-normal">
                                        {item.title}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </motion.div>
                    <div
                        ref={statsRef}
                        className="grid w-full grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-0"
                    >
                        {statisticsCounter?.map((value, index) => {
                            return (
                                <div
                                    key={index}
                                    className="relative flex flex-col items-center justify-center gap-3 px-6 py-5 sm:py-10"
                                >
                                    {index !== 0 && (
                                        <div className="absolute top-1/2 left-0 hidden h-40 w-px -translate-y-1/2 bg-border sm:block" />
                                    )}
                                    <div className="flex gap-0 text-6xl font-medium sm:gap-2 sm:text-7xl md:text-8xl lg:text-9xl">
                                        <Plus
                                            strokeWidth={3.5}
                                            className="h-6 w-6 sm:h-8 sm:w-8 lg:h-12 lg:w-12"
                                        />
                                        <AnimatedCounter
                                            value={value.count}
                                            isInView={isInView}
                                        />
                                    </div>
                                    <p className="text-center text-base font-normal text-muted-foreground">
                                        {value.title}
                                    </p>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>
        </section>
    );
};

export default AboutUs;
