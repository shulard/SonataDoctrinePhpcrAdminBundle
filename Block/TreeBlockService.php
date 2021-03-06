<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DoctrinePHPCRAdminBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TreeBlockService extends BaseBlockService
{
    /**
     * @var array
     */
    protected $defaults;

    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param array           $defaults
     */
    public function __construct($name, EngineInterface $templating, array $defaults = array())
    {
        parent::__construct($name, $templating);
        $this->defaults = $defaults;
    }

    /**
     * {@inheritdoc}
     *
     * NOOP as there is nothing to edit.
     */
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        // there is nothing to edit here!
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        // the callables are a workaround to make bundle configuration win over the default values
        // see https://github.com/sonata-project/SonataDoctrinePhpcrAdminBundle/pull/345
        $resolver->setDefaults(array(
            'template' => function (Options $options, $value) {
                return $value ?: 'SonataDoctrinePHPCRAdminBundle:Block:tree.html.twig';
            },
            'id' => function (Options $options, $value) {
                return $value ?: '/';
            },
            'selected' => function (Options $options, $value) {
                return $value ?: null;
            },
            'routing_defaults' => function (Options $options, $value) {
                return $value ?: $this->defaults;
            },
        ));
    }

    /**
     * {@inheritdoc}
     *
     * NOOP as we do not edit and hence have nothing to validate.
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // there is nothing to validate here
    }
}
