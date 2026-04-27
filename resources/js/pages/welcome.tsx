import { Head } from '@inertiajs/react';
import CTA from '@/components/blocks/cta';
import Faq from '@/components/blocks/faq';
import Footer from '@/components/blocks/footer';
import AgencyHeroSection from '@/components/blocks/hero';

export default function Welcome() {
    return (
        <>
            <Head title="Record Navigation">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
                    rel="stylesheet"
                />
            </Head>
            <AgencyHeroSection />
            <Faq />
            <CTA />
            <Footer />
        </>
    );
}
