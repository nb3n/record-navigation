import HeroSection from '@/components/blocks/hero/hero';
import type { NavigationSection } from '@/components/blocks/hero/header';
import Header from '@/components/blocks/hero/header';
import type { AvatarList } from '@/components/blocks/hero/hero';

export default function AgencyHeroSection() {
    const avatarList: AvatarList[] = [
        {
            image: 'https://images.shadcnspace.com/assets/profiles/user-1.jpg',
        },
        {
            image: 'https://images.shadcnspace.com/assets/profiles/user-2.jpg',
        },
        {
            image: 'https://images.shadcnspace.com/assets/profiles/user-3.jpg',
        },
        {
            image: 'https://images.shadcnspace.com/assets/profiles/user-5.jpg',
        },
    ];

    const navigationData: NavigationSection[] = [
        {
            title: 'Home',
            href: '#',
            isActive: true,
        },
        {
            title: 'About us',
            href: '#',
        },
        {
            title: 'Services',
            href: '#',
        },
        {
            title: 'Team',
            href: '#',
        },
        {
            title: 'Pricing',
            href: '#',
        },
        {
            title: 'Awards',
            href: '#',
        },
    ];

    return (
        <div className="relative">
            <Header navigationData={navigationData} />
            <main>
                <HeroSection avatarList={avatarList} />
            </main>
        </div>
    );
}
