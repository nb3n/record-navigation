import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';
import { Badge } from '@/components/ui/badge';
import { Kbd } from '@/components/ui/kbd';
import { PlusIcon } from 'lucide-react';
import { cn } from '@/lib/utils';

type FaqItem = {
    question: string;
    answer: string | React.ReactNode;
};

const FAQ_DATA: FaqItem[] = [
    {
        question: 'Do I need to add a trait to my page class?',
        answer: 'No. The trait is completely optional. Just drop PreviousRecordAction and NextRecordAction into getHeaderActions() and everything works out of the box using the config-driven defaults.',
    },
    {
        question: 'Which versions of Filament and PHP are supported?',
        answer: 'The package requires PHP 8.2 or higher and supports Filament 4.x and 5.x. For Filament 3.x support, use v1.x of the package.',
    },
    {
        question: 'How does record ordering work?',
        answer: (
            <>
                By default records are ordered by the <Kbd>id</Kbd> column. You
                can change the order column and sort directions by publishing
                the config file and updating the <Kbd>order_column</Kbd>,{' '}
                <Kbd>previous_direction</Kbd>, and <Kbd>next_direction</Kbd>{' '}
                values.
            </>
        ),
    },
    {
        question: 'Can I filter which records are included in the navigation?',
        answer: (
            <>
                Yes. Add the <Kbd>WithRecordNavigation</Kbd> trait to your page
                and override <Kbd>getPreviousRecord()</Kbd> and{' '}
                <Kbd>getNextRecord()</Kbd> with your own query logic — for
                example, filtering by status, tenant, or any custom scope.
            </>
        ),
    },
    {
        question: 'Can I navigate to the edit page instead of the view page?',
        answer: (
            <>
                Yes. Pass <Kbd>NavigationPage::Edit</Kbd> to the{' '}
                <Kbd>navigateTo()</Kbd> method on either action. Each action can
                target a different page type independently, and custom routes
                registered in <Kbd>getPages()</Kbd> are also supported via{' '}
                <Kbd>NavigationPage::custom()</Kbd>.
            </>
        ),
    },
    {
        question: 'Will the buttons break if there is no adjacent record?',
        answer: 'No. The buttons automatically disable and turn gray when there is no previous or next record, so users always get clear visual feedback at the boundaries.',
    },
    {
        question: 'How many database queries does each action fire per render?',
        answer: (
            <>
                Exactly one per action per render. The package caches the
                resolved adjacent record internally so the <Kbd>color</Kbd>,{' '}
                <Kbd>disabled</Kbd>, and <Kbd>url</Kbd> closures all share the
                same query result.
            </>
        ),
    },
];

export default function Faq() {
    return (
        <section>
            <div className="mx-auto flex max-w-7xl flex-col gap-16 px-4 py-8 sm:px-6 lg:px-8 xl:py-24">
                <div className="flex animate-in flex-col items-center gap-4 delay-100 duration-1000 ease-in-out fill-mode-both slide-in-from-top-10 fade-in">
                    <Badge
                        variant="outline"
                        className="h-auto border-0 px-3 py-1 text-sm outline outline-border"
                    >
                        FAQs
                    </Badge>
                    <h2 className="max-w-lg text-center text-5xl font-medium">
                        Got questions? We’ve got answers ready
                    </h2>
                </div>
                <div>
                    <Accordion
                        type="single"
                        className="flex w-full flex-col gap-6"
                    >
                        {FAQ_DATA.map((faq, index) => (
                            <AccordionItem
                                key={`item-${index}`}
                                value={`item-${index}`}
                                className={cn(
                                    'group/item flex animate-in flex-col gap-3 rounded-2xl border border-border p-6 transition-colors duration-700 fill-mode-both fade-in slide-in-from-bottom-8 data-[open]:bg-accent',
                                    index === 0 && 'delay-100',
                                    index === 1 && 'delay-200',
                                    index === 2 && 'delay-300',
                                    index === 3 && 'delay-400',
                                    index === 4 && 'delay-500',
                                    index === 5 && 'delay-600',
                                    index === 6 && 'delay-700',
                                )}
                            >
                                <AccordionTrigger className="cursor-pointer p-0 text-xl font-medium hover:no-underline **:data-[slot=accordion-trigger-icon]:hidden">
                                    {faq.question}
                                    <PlusIcon className="h-6 w-6 shrink-0 transition-transform duration-200 group-aria-expanded/accordion-trigger:rotate-45" />
                                </AccordionTrigger>
                                <AccordionContent className="p-0 text-base text-muted-foreground">
                                    {faq.answer}
                                </AccordionContent>
                            </AccordionItem>
                        ))}
                    </Accordion>
                </div>
            </div>
        </section>
    );
}
